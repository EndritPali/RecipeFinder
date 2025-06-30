import { useState, useEffect } from 'react';
import auth from '@/lib/api/auth';
import ForbiddenPage from '../Components/ui/ForbiddenPage';

export default function IsAuthenticatedGuard({ children }) {

    const [isAuthenticated, setIsAuthenticated] = useState(false);

    useEffect(() => {
        const checkAuth = async () => {
            const authenticated = auth.isAuthenticated();
            setIsAuthenticated(authenticated);
        };

        checkAuth();
    }, []);



    if (!isAuthenticated) {
        return <ForbiddenPage />;
    }

    return children;
}