export interface UseFetchCommentsOptions {
  enabled?: boolean;
}

export interface Comment {
  id: string;
  user: string;
  recipe: string;
  content: string;
  created_at: string;
  updated_at: string;
}

export interface CommentFormValues {
  content: string;
}

export interface EditCommentPayload {
  id: string;
  description: string;
}

export interface ToggleLikePayload {
  id: string;
  action: "like" | "unlike";
}

export interface MappedComment {
  id: string;
  comment: string;
  name: string;
  likes: number;
  date: string;
  userHasLiked: boolean;
  creator: string;
}

export interface TemplateProps {
  comment: string;
  name: string;
  likes: number;
  date: string;
  buttons: any;
  onLikeToggle: () => void;
  hasLiked: boolean;
  avatar: any;
}

export interface RecentComment {
  id: string | number;
  description: string;
  posted_at: string;
  user?: {
    username?: string;
  };
}
