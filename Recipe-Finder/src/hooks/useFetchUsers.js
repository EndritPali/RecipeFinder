import { useState, useEffect, useCallback } from "react";
import api from "../Services/api";

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

export const useFetchUsers = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);
  const [pagination, setPagination] = useState({
    current: 1,
    pageSize: 10,
    total: 0,
    showSizeChanger: true,
    showTotal: (total) => `${total} User(s) in total`,
  });

  const fetchUsers = useCallback(async (page = 1, pageSize = 10) => {
    setLoading(true);
    try {
      const response = await api.get("v1/users", {
        params: {
          page: page,
          per_page: pageSize,
        },
      });
      const mapped = response.data.data.map((user) => ({
        key: user.id,
        username: user.username,
        email: user.email,
        role: user.role,
        date: dayjs(user.created_at).format("DD-MM-YYYY"),
      }));

      
      setUsers(mapped);
      
      setPagination((prev) => ({
        ...prev,
        current: response.data.meta.current_page,
        total: response.data.meta.total,
        pageSize: response.data.meta.per_page,
      }));
    } catch (error) {
      console.error("Error fetching users:", error);
    } finally {
      setLoading(false);
    }
  }, []);

  const handleTableChange = useCallback(
    (paginationConfig) => {
      fetchUsers(paginationConfig.current, paginationConfig.pageSize);
    },
    [fetchUsers]
  );

  useEffect(() => {
    fetchUsers(1, 10);
  }, [fetchUsers]);

  return { users, loading, fetchUsers, handleTableChange, pagination };
};
