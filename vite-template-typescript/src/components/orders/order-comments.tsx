import * as React from "react";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import Stack from "@mui/material/Stack";
import TextField from "@mui/material/TextField";
import Typography from "@mui/material/Typography";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";

import { addOrderComment, getOrderComments } from "@/api/orders";

export interface OrderCommentsProps {
  orderId: string;
}

export function OrderComments({ orderId }: OrderCommentsProps): React.JSX.Element {
  const queryClient = useQueryClient();
  const { data: comments = [] } = useQuery({
    queryKey: ["orders", orderId, "comments"],
    queryFn: () => getOrderComments(orderId),
  });

  const [message, setMessage] = React.useState("");

  const mutation = useMutation({
    mutationFn: (msg: string) => addOrderComment(orderId, msg),
    onSuccess: () => {
      setMessage("");
      queryClient.invalidateQueries({ queryKey: ["orders", orderId, "comments"] });
    },
  });

  return (
    <Stack spacing={2} sx={{ mt: 2 }}>
      <Stack spacing={1} sx={{ maxHeight: 200, overflowY: "auto" }}>
        {comments.map((c) => (
          <Box key={c.id} sx={{ p: 1, bgcolor: "var(--mui-palette-background-level1)", borderRadius: 1 }}>
            <Typography variant="body2">{c.message}</Typography>
          </Box>
        ))}
        {comments.length === 0 ? (
          <Typography color="text.secondary" variant="body2">
            No comments
          </Typography>
        ) : null}
      </Stack>
      <Stack direction="row" spacing={1}>
        <TextField
          value={message}
          onChange={(e) => setMessage(e.target.value)}
          size="small"
          placeholder="Add a comment"
          fullWidth
        />
        <Button
          disabled={message.trim().length === 0}
          onClick={() => mutation.mutate(message)}
          variant="contained"
        >
          Post
        </Button>
      </Stack>
    </Stack>
  );
}

