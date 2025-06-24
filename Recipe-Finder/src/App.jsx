import { Routes, Route } from 'react-router-dom';
import Router from './router/Router';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import './App.scss';

function App() {
  return (
    <>
      <Routes>
        <Route path="/*" element={<Router />} />
      </Routes>
      <ReactQueryDevtools initialIsOpen={false} />
    </>
  );
}

export default App;
