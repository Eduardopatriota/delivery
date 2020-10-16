// Imports: Dependencies
import React from 'react';
import { Provider } from 'react-redux';
import { Store   } from './src/redux/store';
import Navegacao from './Navegacao'


export default function App() {
  return (
    // Redux: Global Store
    <Provider store={Store}>
        <Navegacao />
    </Provider>
  );
};