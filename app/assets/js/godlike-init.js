import { options } from './parts/_options';

import './godlike.js';

if (typeof Godlike !== 'undefined') {
    Godlike.setOptions(options);
    Godlike.init();
}
