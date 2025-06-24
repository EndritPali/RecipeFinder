import { createContext, useContext } from 'react';
import { useAuthHook } from '../hooks/useAuthHook';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const auth = useAuthHook();

    return (
        <AuthContext.Provider value={auth}>
            {children}
        </AuthContext.Provider>
    );
}

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
}; 