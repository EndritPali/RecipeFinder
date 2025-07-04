import { useEffect } from "react";

export function useCommentEvents(onNewComment: any) {
  useEffect(() => {
    const channel = window.Echo.channel("comments");
    channel.listen(".CommentCreated", (event: any) => {
      onNewComment(event.comment);
    });
    return () => {
      channel.stopListening(".CommentCreated");
    };
  }, [onNewComment]);
}
