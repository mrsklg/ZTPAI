// 
// import './bootstrap.js';
//
// import React from 'react';
// import ReactDOM from 'react-dom';
// import { createRoot } from 'react-dom/client';
// import Nav from './js/components/Nav';
//
// const container = document.getElementById('nav-root');
// const root = createRoot(container); // createRoot(container!) if you use TypeScript
//
// const App = () => (
//     <div>
//         <h1>Hello, React with Symfony!</h1>
//     </div>
// );
//
// // root.render(<Nav />);
//
// /*
//  * Welcome to your app's main JavaScript file!
//  *
//  * We recommend including the built version of this JavaScript file
//  * (and its CSS file) in your base layout (base.html.twig).
//  */
//
// // any CSS you import will output into a single css file (app.css in this case)
// // import './styles/app.css';
//
//

import React from 'react';
import { createRoot } from 'react-dom/client';

import Nav from './js/components/Nav';
import Dashboard from './js/components/Dashboard';
import Index from './js/components/Index';
import Settings from './js/components/Settings';
import SettingsAdmin from './js/components/SettingsAdmin';
import Stats from './js/components/Stats';

const App = ({ showNav, view }) => {
    let ContentView;
    switch(view) {
        case 'dashboard':
            ContentView = Dashboard;
            break;
        case 'index':
            ContentView = Index;
            break;
        case 'settings':
            ContentView = Settings;
            break;
        case 'settings_admin':
            ContentView = SettingsAdmin;
            break;
        case 'stats':
            ContentView = Stats;
            break;
        default:
            ContentView = () => <div>Not Found</div>;
    }

    return (
        <div>
            {showNav && <Nav />}
            <ContentView />
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const rootElement = document.getElementById('root');
    const root = createRoot(rootElement);
    if (root) {
        const showNav = rootElement.getAttribute('data-show-nav') === 'true';
        const view = rootElement.getAttribute('data-view');
        root.render(<App showNav={showNav} view={view} />);
    }
});
