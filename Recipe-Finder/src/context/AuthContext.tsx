import { createContext, ReactNode, useContext } from 'react';
import { useAuthHook } from '../features/auth/hooks/useAuthHook';
import { AuthContextType } from '@/types/auth';

const AuthContext = createContext<AuthContextType | null >(null);

export function AuthProvider({ children } : {children: ReactNode}) {
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