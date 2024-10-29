import './style.css'
import { createApp } from './main'

let app;
globalThis.vueApps = {
    ...(globalThis.vueApps ?? {}),
    
    createTestPage(data?: Object) {
        if(!app){
            app = createApp(data);
        }
        return app;
    }
}
