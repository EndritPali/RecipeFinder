import api from '../Services/api';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import auth from '../Services/auth';
import { useCallback } from 'react';

export function useAuthHook() {
    const queryClient = useQueryClient();

    const {
        data: user,
        isLoading,
        isError,
        error,
    } = useQuery({
        queryKey: ['currentUser'],
        queryFn: auth.getCurrentUser,
        staleTime: 1000 * 60 * 5,
        retry: false,
        onSuccess: (data) => {
            if (data) {
                localStorage.setItem('user', JSON.stringify(data));
            } else {
                localStorage.removeItem('user');
            }
        },
        onError: () => {
            localStorage.removeItem('user')
            localStorage.removeItem('token')
        }
    })

    const loginMutation = useMutation({
        mutationFn: async ({ email, password }) => {
            const response = await api.post('v1/auth/login', { email, password });
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
            return response.data.user
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['currentUser'] })
        },
        onError: () => {
            localStorage.removeItem('user')
            localStorage.removeItem('token')
        }
    })

    const registerMutation = useMutation({
        mutationFn: async ({ username, email, password }) => {
            const response = await api.post('v1/auth/register', {
                username,
                email,
                password,
                role: 'User',
            });
            return response.data;
        }
    })

    const logoutMutation = useMutation({
        mutationFn: async () => {
            const response = await api.post('v1/auth/logout');
            return response.data.user
        },
        onSuccess: () => {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            queryClient.invalidateQueries({ queryKey: ['currentUser'] });
            queryClient.clear()
        }
    })

    const isAuthenticated = !!user;

    const fetchPendingRequests = useCallback(async () => {
        if (!isAuthenticated || user?.role !== 'Admin') return 0;
        try {
            const token = localStorage.getItem('token');
            if (!token) return 0;
            api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            const response = await api.get('v1/auth/password-reset/pending');
            return Array.isArray(response.data?.data) ? response.data.data.length : 0;
        } catch (error) {
            console.error('Error fetching pending requests:', error);
            return 0;
        }
    }, [isAuthenticated, user?.role]);

    return {
        user,
        isAuthenticated,
        isLoading,
        isError,
        error,
        loginMutation,
        registerMutation,
        logoutMutation,
        login: loginMutation.mutateAsync,
        register: registerMutation.mutateAsync,
        registerStatus: {
            isLoading: registerMutation.isLoading,
            isError: registerMutation.isError,
            error: registerMutation.error,
            data: registerMutation.data,
        },
        logout: logoutMutation.mutateAsync,
        logoutStatus: {
            isLoading: logoutMutation.isLoading,
        },
        fetchPendingRequests,
    };
}