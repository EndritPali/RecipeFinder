// hooks/useAuth.js
import { useState, useEffect, useCallback } from 'react';
import api from '../Services/api';
import auth from '../Services/auth';

export default function useAuth() {
    const [currentUser, setCurrentUser] = useState(() => {
        const storedUser = localStorage.getItem('user');
        return storedUser ? JSON.parse(storedUser) : null;
    });
    
    const [isAuthenticated, setIsAuthenticated] = useState(!!localStorage.getItem('token'));

    useEffect(() => {
        const fetchUserData = async () => {
            try {
                const userData = await auth.getCurrentUser();
                if (userData) {
                    setCurrentUser(userData);
                    setIsAuthenticated(true);
                }
            } catch (error) {
                console.error('Error fetching user data:', error);
                setIsAuthenticated(false);
            }
        };

        fetchUserData();
    }, []);


    const fetchPendingRequests = useCallback(async () => {
        if (!isAuthenticated || currentUser?.role !== 'Admin') return 0;
        
        try {
            const token = localStorage.getItem('token');
            if (!token) return 0;

            api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            const response = await api.get('/auth/password-reset/pending');
            return Array.isArray(response.data?.data) ? response.data.data.length : 0;
        } catch (error) {
            console.error('Error fetching pending requests:', error);
            return 0;
        }
    }, [isAuthenticated, currentUser?.role]);

    const logout = async () => {
        try {
            const token = localStorage.getItem('token');
            if (token) {
                api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                await api.post('v1/auth/logout');
            }
            
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            setCurrentUser(null);
            setIsAuthenticated(false);
            return true;
        } catch (error) {
            console.error('Logout error:', error);
            return false;
        }
    };

    return { 
        currentUser, 
        isAuthenticated,
        user: currentUser, 
        logout,
        fetchPendingRequests
    };
}