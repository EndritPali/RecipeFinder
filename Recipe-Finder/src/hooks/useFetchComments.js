import { useState, useEffect, useCallback } from "react";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import api from "../Services/api";

dayjs.extend(relativeTime);

export const useFetchComments = () => {
  const [comments, setComments] = useState([]);
  const [loading, setLoading] = useState(false);
  const [refreshFlag, setRefreshFlag] = useState(0);
  const [pagination, setPagination] = useState({
    current: 1,
    pageSize: 10,
    total: 0,
    showSizeChanger: true,
  });

  const fetchComments = useCallback(async (page = 1, pageSize = 15) => {
    setLoading(true);
    try {
      const response = await api.get("v1/comments", {
        params: {
          page: page,
          per_page: pageSize,
        },
      });

      const mapped = response.data.data.map((comment) => ({
        id: comment.id,
        creator: comment.creator_id,
        comment: comment.description,
        name: comment.user_id || "Anonymous",
        likes: comment.likes || 0,
        date: dayjs(comment.created_at).fromNow(),
      }));

      setComments(mapped);

      setPagination((prev) => ({
        ...prev,
        current: response.data.meta.current_page,
        total: response.data.meta.total,
        pageSize: response.data.meta.per_page,
      }));
    } catch (error) {
      console.error("Error fetching comments:", error);
    } finally {
      setLoading(false);
    }
  }, []);

  const refreshComments = useCallback(() => {
    setRefreshFlag((prev) => prev + 1);
  }, []);

  useEffect(() => {
    fetchComments(1, 3);
  }, [fetchComments, refreshFlag]);

    const handleTableChange = useCallback(
    (paginationConfig) => {
      fetchComments(paginationConfig.current, paginationConfig.pageSize);
    },
    [fetchComments]
  );

  return { comments, loading, refreshComments, handleTableChange, pagination };
};
