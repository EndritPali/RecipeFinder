import { Routes, Route } from 'react-router-dom';
import Router from './router/Router';
import { ModalProvider } from './Context/ModalProvider';
import './App.scss';

function App() {
  return (
    <>
      <ModalProvider>
        <Routes>
          <Route path="/*" element={<Router />} />
        </Routes>
      </ModalProvider>
    </>
  );
}

export default App;
