import './style.css'
import { createApp } from './main'

let app;
globalThis.vueApps = {
    ...(globalThis.vueApps ?? {}),
    
    createTestPage(data) {
        if(!app){
            app = createApp(data);
        }
        return app;
    }
}
