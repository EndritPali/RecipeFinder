import  { useState, useEffect } from 'react';
import auth from '../Services/auth';
import ForbiddenPage from '../Components/ForbiddenPage';

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