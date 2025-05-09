import React, { useState, useEffect } from 'react';
import { Navigate } from 'react-router-dom';
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