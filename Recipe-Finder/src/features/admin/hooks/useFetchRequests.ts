import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/lib/api/api";
import { message } from "antd";
import { ResetRequest } from "@/types/admin";

export function useFetchResetRequests(enabled: boolean = true) {
  const queryClient = useQueryClient();

  const query = useQuery<ResetRequest[]>({
    queryKey: ["reset-requests"],
    queryFn: async () => {
      try {
        const response = await api.get("v1/auth/password-reset/pending");
        return response.data.data || [];
      } catch (error) {
        message.error("Failed to load password reset requests");
        throw error;
      }
    },
    staleTime: 1000 * 60,
    enabled,
  });

  const mutation = useMutation({
    mutationFn: async ({
      resetId,
      action,
    }: {
      resetId: string;
      action: "approve" | "deny";
    }) => {
      return api.post("v1/auth/password-reset/process", {
        reset_id: resetId,
        action,
      });
    },
    onSuccess: (response, variables) => {
      if (variables.action === "approve") {
        const { temporary_password, user_email } = response.data;
        message.success(`Password reset approved for ${user_email}.`);
      } else {
        message.success("Password reset request denied");
      }
      queryClient.invalidateQueries({ queryKey: ["reset-requests"] });
    },
    onError: (_error, variables) => {
      message.error(`Failed to ${variables.action} password reset request`);
    },
  });

  return {
    ...query,
    approveOrDeny: mutation.mutateAsync,
    isMutating: mutation.isPending,
  };
}
