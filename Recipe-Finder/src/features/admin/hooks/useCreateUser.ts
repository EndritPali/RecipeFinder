import { useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api/api';
import { message } from 'antd';

interface ApiError {
  response?: {
    data?: {
      message?: string;
    };
  };
}

export const useCreateUser = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (payload: Record<string, any>) => {
      const { data } = await api.post('v1/users', payload);
      return data;
    },
    onSuccess: () => {
      message.success('User created successfully');
      queryClient.invalidateQueries({ queryKey: ['users'] });
    },
    onError: (error: ApiError) => {
      message.error(error.response?.data?.message || 'User creation failed');
    },
  });
}; 