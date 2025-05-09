import ForbiddenPage from "../Components/ForbiddenPage";
import React, { useState, useEffect } from 'react';
import { Navigate } from 'react-router-dom';
import auth from '../Services/auth';

export default function IsAdminGuard({ children }) {

    const [isAdmin, setIsAdmin] = useState(false);

    useEffect(() => {
        const checkAdminRole = async () => {
            const hasAdminRole = await auth.hasRole('Admin');
            setIsAdmin(hasAdminRole);

        };

        checkAdminRole();
    }, []);


    if (!isAdmin) {
        return <ForbiddenPage />;
    }

    return children;
}