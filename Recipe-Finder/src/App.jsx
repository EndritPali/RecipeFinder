import { Routes, Route } from 'react-router-dom';
import Router from './router/Router';
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
