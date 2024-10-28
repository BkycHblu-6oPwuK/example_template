import express from 'express';
import path from 'path';
import fs from 'fs/promises';
import dotenv from 'dotenv';

dotenv.config({ path: path.resolve(path.dirname(new URL(import.meta.url).pathname), '../../../local/php_interface/include/.env') });
const port = process.env.VITE_SSR_PORT || 5174;
const app = express();

async function getRenderModule(page) {
  const serverEntry = path.resolve(`./dist/server/${page}.js`);
  try {
    await fs.access(serverEntry);
  } catch (err) {
    return null;
  }
  const { render } = await import(path.resolve(serverEntry));
  return render;
}

app.use('/:page', async (req, res) => {
  try {
    const page = req.params.page;;
    const render = await getRenderModule(page);
    if (!render) {
      res.status(404).send(`Cannot GET /${page}`);
      return;
    }
    
    const { stream } = render();
    res.status(200).set({ 'Content-Type': 'text/html' });

    for await (const chunk of stream) {
      if (res.closed) break;
      res.write(chunk);
    }
    res.end();
  } catch (e) {
    console.error(e.stack);
    res.status(500).end(e.stack);
  }
});

app.listen(port, () => {
  console.log(`Server started at http://localhost:${port}`);
});
