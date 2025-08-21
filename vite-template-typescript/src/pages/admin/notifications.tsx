import * as React from "react";
import Box from "@mui/material/Box";
import Card from "@mui/material/Card";
import CardContent from "@mui/material/CardContent";
import CardHeader from "@mui/material/CardHeader";
import Stack from "@mui/material/Stack";
import Switch from "@mui/material/Switch";
import Typography from "@mui/material/Typography";
import { Helmet } from "react-helmet-async";

import { appConfig } from "@/config/app";
import {
  addNotificationComment,
  getNotifications,
  updateNotificationStatus,
  type Notification,
} from "@/api/admin";
import type { Metadata } from "@/types/metadata";
import { CommentThread } from "@/components/admin/comment-thread";

const metadata = { title: `Notifications | Admin | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
  const [notifications, setNotifications] = React.useState<Notification[]>([]);

  React.useEffect(() => {
    getNotifications()
      .then((data) => setNotifications(data))
      .catch(() => setNotifications([]));
  }, []);

  const handleStatusChange = async (id: string, checked: boolean): Promise<void> => {
    const status = checked ? "resolved" : "open";
    setNotifications((prev) =>
      prev.map((n) => (n.id === id ? { ...n, status } : n))
    );
    try {
      await updateNotificationStatus(id, status);
    } catch {
      // ignore
    }
  };

  const handleAddComment = async (id: string, content: string): Promise<void> => {
    try {
      const comment = await addNotificationComment(id, content);
      setNotifications((prev) =>
        prev.map((n) =>
          n.id === id ? { ...n, comments: [...n.comments, comment] } : n
        )
      );
    } catch {
      // ignore
    }
  };

  return (
    <React.Fragment>
      <Helmet>
        <title>{metadata.title}</title>
      </Helmet>
      <Box
        sx={{
          maxWidth: "var(--Content-maxWidth)",
          m: "var(--Content-margin)",
          p: "var(--Content-padding)",
          width: "var(--Content-width)",
        }}
      >
        <Stack spacing={4}>
          <Typography variant="h4">Notifications</Typography>
          <Stack spacing={3}>
            {notifications.map((item) => (
              <Card key={item.id}>
                <CardHeader
                  title={item.message}
                  action={
                    <Switch
                      checked={item.status === "resolved"}
                      onChange={(event) =>
                        handleStatusChange(item.id, event.target.checked)
                      }
                    />
                  }
                />
                <CardContent>
                  <CommentThread
                    comments={item.comments}
                    onAdd={(content) => handleAddComment(item.id, content)}
                  />
                </CardContent>
              </Card>
            ))}
          </Stack>
        </Stack>
      </Box>
    </React.Fragment>
  );
}
