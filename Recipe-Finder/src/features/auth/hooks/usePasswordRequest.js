import { useMutation } from "@tanstack/react-query";
import api from "@/lib/api/api";
import { message } from "antd";

export function usePasswordResetRequest() {
    return useMutation({
        mutationFn: async (payload) => {
            const { data } = await api.post('v1/auth/password-reset/submit', payload);
            return data;
        },
        onSuccess: () => {
            message.success('Password reset request submitted. An administrator will review your request');
        },
        onError: (error) => {
            message.error(error.response?.data?.message || 'Error submitting reset request')
        }
    })
}