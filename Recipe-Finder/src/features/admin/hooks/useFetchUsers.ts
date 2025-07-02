import { useQuery, UseQueryOptions } from "@tanstack/react-query";
import api from "@/lib/api/api";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { UserType } from '@/types/admin';
dayjs.extend(relativeTime);

interface MappedUser extends UserType {
  key: string;
  date: string;
}

export interface FetchUsersResponse {
  users: MappedUser[];
  meta: any;
}

const fetchUsers = async (page = 1, pageSize = 10): Promise<FetchUsersResponse> => {
  const { data } = await api.get("v1/users", {
    params: {
      page: page,
      per_page: pageSize,
    },
  });

  const mappedUsers: MappedUser[] = data.data.map((user: UserType) => ({
    ...user,
    key: user.id,
    date: dayjs(user.created_at).format("DD-MM-YYYY"),
  }));

  return {
    users: mappedUsers,
    meta: data.meta,
  };
};

export const useFetchUsers = (
  page: number,
  pageSize: number,
  options?: { enabled?: boolean }
) => {
  const enabled = options?.enabled !== undefined ? options.enabled : true;

  return useQuery<FetchUsersResponse, Error>({
    queryKey: ["users", page, pageSize],
    queryFn: () => fetchUsers(page, pageSize),
    enabled,
  });
};
