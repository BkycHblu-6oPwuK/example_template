import grpc from '@grpc/grpc-js';
import protoLoader from '@grpc/proto-loader';
import path from 'path';
import fs from 'fs/promises';
import dotenv from 'dotenv';

dotenv.config({ path: path.resolve('../../../local/php_interface/include/.env') });
const port = process.env.VITE_SSR_PORT || 5174;

const packageDefinition = protoLoader.loadSync(path.resolve('../../../local/proto/ssr.proto'), {});
const ssrProto = grpc.loadPackageDefinition(packageDefinition).Ssr;

async function getRenderModule(page) {
  const serverEntry = path.resolve(`./dist/server/${page}.js`);
  try {
    await fs.access(serverEntry);
  } catch (err) {
    return null;
  }
  const { render } = await import(serverEntry);
  return render;
}

const server = new grpc.Server();
server.addService(ssrProto.SSRService.service, {
  RenderPage: async (call, callback) => {
    const { page, data } = call.request;
    const render = await getRenderModule(page);
    if (!render) {
      callback({
        code: grpc.status.NOT_FOUND,
        message: `Cannot render page ${page}`,
      });
      return;
    }
    const parsedData = data ? JSON.parse(data) : null;
    const { stream } = render(parsedData);
    const decoder = new TextDecoder('utf-8'); 
    let html = '';
    for await (const chunk of stream) {
      const stringChunk = decoder.decode(chunk, { stream: true });
      html += stringChunk;
    }
    callback(null, { html });
  },
});

server.bindAsync(`0.0.0.0:${port}`, grpc.ServerCredentials.createInsecure(), () => {
  console.log(`gRPC server running at http://localhost:${port}`);
  server.start();
});
