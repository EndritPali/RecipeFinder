import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import api from "../Services/api";
import { useQuery } from "@tanstack/react-query";

dayjs.extend(relativeTime);

const fetchComments = async (page = 1, pageSize = 15) => {
  const { data } = await api.get("v1/comments", {
    params: {
      page: page,
      per_page: pageSize,
    },
  });

  const mappedComments = data.data.map((comment) => ({
    id: comment.id,
    creator: comment.creator_id,
    comment: comment.description,
    name: comment.user_id || "Anonymous",
    likes: comment.likes || 0,
    date: dayjs(comment.created_at).fromNow(),
    userHasLiked: comment.user_has_liked,
  }));

  return {
    comments: mappedComments,
    meta: data.meta,
  };
};

export const useFetchComments = (page, pageSize, options = {}) => {
  const { enabled = true } = options;

  return useQuery({
    queryKey: ["comments", page, pageSize],
    queryFn: () => fetchComments(page, pageSize),
    keepPreviousData: true,
    enabled,
  });
};
