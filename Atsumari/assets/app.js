import React from 'react';
import { createRoot } from 'react-dom/client';

import Nav from './js/components/Nav';
import Dashboard from './js/components/Dashboard';
import Index from './js/components/Index';
import Settings from './js/components/Settings';
import SettingsAdmin from './js/components/SettingsAdmin';
import Stats from './js/components/Stats';
import Login from './js/components/Login';
import ReadingSession from './js/components/ReadingSession';
import Collection from './js/components/Collection';
import CurrentBook from './js/components/CurrentBook';
import BookDetails from './js/components/BookDetails';

const App = ({ showNav, view, role }) => {
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
        case 'login':
            ContentView = Login;
            break;
        case 'reading_session':
            ContentView = ReadingSession;
            break;
        case 'books':
            ContentView = Collection;
            break;
        case 'current_book':
            ContentView = CurrentBook;
            break;
        case 'book_details':
            ContentView = BookDetails;
            break;
        default:
            ContentView = () => <div>Not Found</div>;
    }

    return (
        <div>
            {showNav && <Nav role={role.role}/>}
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
        const role = rootElement.getAttribute('data-role');
        root.render(<App showNav={showNav} view={view} role={{role}}/>);
    }
});
