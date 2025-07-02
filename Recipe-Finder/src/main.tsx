import { StrictMode } from 'react'
import '@ant-design/v5-patch-for-react-19';
import { BrowserRouter } from 'react-router-dom';
import { createRoot } from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { AuthProvider } from './context/AuthContext';
import './index.scss'
import App from './App.js'

const queryClient = new QueryClient();
const rootElement = document.getElementById('root');
if (!rootElement) throw new Error("Failed to find the root element");

createRoot(rootElement).render(
  <StrictMode>
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <BrowserRouter>
          <App />
        </BrowserRouter>
      </AuthProvider>
    </QueryClientProvider>
  </StrictMode>,
)
