import { useMutation } from "@tanstack/react-query";
import { AxiosError, isAxiosError } from "axios";
import api from "@/lib/api/api";
import { message } from "antd";
import { PasswordResetPayload } from '@/types/auth';

export function usePasswordResetRequest() {
    return useMutation<any, Error, PasswordResetPayload>({
        mutationFn: async (payload) => {
            const { data } = await api.post('v1/auth/password-reset/submit', payload);
            return data;
        },
        onSuccess: () => {
            message.success('Password reset request submitted. An administrator will review your request');
        },
        onError: (error) => {
            if (isAxiosError(error)) {
                message.error(error.response?.data?.message || 'Error submitting reset request');
            } else {
                message.error('Error submitting reset request');
            }
        }
    })
}