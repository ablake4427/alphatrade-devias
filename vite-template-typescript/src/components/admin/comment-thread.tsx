import * as React from "react";
import Button from "@mui/material/Button";
import Stack from "@mui/material/Stack";
import TextField from "@mui/material/TextField";

import { CommentBox } from "@/components/dashboard/social/comment-box";
import type { Comment } from "@/api/admin";

export interface CommentThreadProps {
  comments: Comment[];
  onAdd?: (content: string) => void;
}

export function CommentThread({ comments, onAdd }: CommentThreadProps): React.JSX.Element {
  const [value, setValue] = React.useState("");

  const handleSubmit = (): void => {
    const content = value.trim();
    if (!content) {
      return;
    }
    onAdd?.(content);
    setValue("");
  };

  return (
    <Stack spacing={2} sx={{ mt: 1 }}>
      {comments.map((comment) => (
        <CommentBox comment={comment} key={comment.id} />
      ))}
      {onAdd ? (
        <Stack direction="row" spacing={1} sx={{ alignItems: "center" }}>
          <TextField
            value={value}
            onChange={(e) => setValue(e.target.value)}
            placeholder="Add comment"
            size="small"
            fullWidth
          />
          <Button onClick={handleSubmit} variant="contained">
            Post
          </Button>
        </Stack>
      ) : null}
    </Stack>
  );
}
