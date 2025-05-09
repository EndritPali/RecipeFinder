import { Routes, Route } from 'react-router-dom'; // You still need this for the routes container
import Router from './router/Router'; // Import your Router component with `useRoutes`

import './App.scss';

function App() {
  return (
    <>
      <Routes> 
        <Route path="/*" element={<Router />} /> 
      </Routes>
    </>
  );
}

export default App;
