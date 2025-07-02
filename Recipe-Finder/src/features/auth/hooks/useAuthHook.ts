import api from '@/lib/api/api';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import auth from '@/lib/api/auth';
import { useCallback, useEffect } from 'react';
import { User } from '@/types/user';
import { LoginPayload, RegisterPayload } from '@/types/auth';

export function useAuthHook() {
    const queryClient = useQueryClient();

    const {
        data: userRaw,
        isLoading,
        isError,
        error,
    } = useQuery<User>({
        queryKey: ['currentUser'],
        queryFn: auth.getCurrentUser,
        staleTime: 1000 * 60 * 5,
        retry: false,
    })

    const user = userRaw ?? null;

    useEffect(() => {
        if (user) {
            localStorage.setItem('user', JSON.stringify(user));
        }
    }, [user])

    useEffect(() => {
        if (isError) {
            localStorage.removeItem('user');
            localStorage.removeItem('token')
        }
    }, [isError])

    const loginMutation = useMutation<User, Error, LoginPayload>({
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

    const registerMutation = useMutation<any, Error, RegisterPayload>({
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
        isLoadingAuth: isLoading,
        isError,
        error,
        loginMutation,
        registerMutation,
        logoutMutation,
        login: loginMutation.mutateAsync,
        register: registerMutation.mutateAsync,
        registerStatus: {
            isLoading: registerMutation.isPending,
            isError: registerMutation.isError,
            error: registerMutation.error,
            data: registerMutation.data,
        },
        logout: logoutMutation.mutateAsync,
        logoutStatus: {
            isLoading: logoutMutation.isPending,
        },
        fetchPendingRequests,
    };
}