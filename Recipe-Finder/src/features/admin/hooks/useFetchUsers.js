import { useQuery } from "@tanstack/react-query";
import api from "@/lib/api/api";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

const fetchUsers = async (page = 1, pageSize = 10) => {
  const { data } = await api.get("v1/users", {
    params: {
      page: page,
      per_page: pageSize,
    },
  });

  const mappedUsers = data.data.map((user) => ({
    key: user.id,
    username: user.username,
    email: user.email,
    role: user.role,
    date: dayjs(user.created_at).format("DD-MM-YYYY"),
  }));

  return {
    users: mappedUsers,
    meta: data.meta,
  };
};

export const useFetchUsers = (page, pageSize, options = {}) => {
  const { enabled = true } = options;

  return useQuery({
    queryKey: ["users", page, pageSize],
    queryFn: () => fetchUsers(page, pageSize),
    keepPreviousData: true,
    enabled,
  });
};
