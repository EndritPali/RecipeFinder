import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/lib/api/api";
import { message } from "antd";

interface ApiError {
    response?: {
        data?: {
            message?: string;
        };
    };
}

export const useDeleteUsers = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (userId: string) => {
            return api.delete(`v1/users/${userId}`);
        },
        onSuccess: () => {
            message.success("User deleted successfully!");
            queryClient.invalidateQueries({ queryKey: ["users"] });
        },
        onError: (error: ApiError) => {
            console.error("Error deleting user:", error);
            message.error(error.response?.data?.message || "Failed to delete user.");
        },
    });
};