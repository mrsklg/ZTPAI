import { registerReactControllerComponents } from '@symfony/ux-react';
import './bootstrap.js';

import React from 'react';
import ReactDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import Nav from './js/components/Nav';

const container = document.getElementById('nav-root');
const root = createRoot(container); // createRoot(container!) if you use TypeScript

const App = () => (
    <div>
        <h1>Hello, React with Symfony!</h1>
    </div>
);

// root.render(<Nav />);

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';

registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));