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
  addBugReportComment,
  getBugReports,
  updateBugReportStatus,
  type BugReport,
} from "@/api/admin";
import type { Metadata } from "@/types/metadata";
import { CommentThread } from "@/components/admin/comment-thread";

const metadata = { title: `Bug reports | Admin | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
  const [reports, setReports] = React.useState<BugReport[]>([]);

  React.useEffect(() => {
    getBugReports()
      .then((data) => setReports(data))
      .catch(() => setReports([]));
  }, []);

  const handleStatusChange = async (id: string, checked: boolean): Promise<void> => {
    const status = checked ? "resolved" : "open";
    setReports((prev) =>
      prev.map((r) => (r.id === id ? { ...r, status } : r))
    );
    try {
      await updateBugReportStatus(id, status);
    } catch {
      // ignore
    }
  };

  const handleAddComment = async (id: string, content: string): Promise<void> => {
    try {
      const comment = await addBugReportComment(id, content);
      setReports((prev) =>
        prev.map((r) =>
          r.id === id ? { ...r, comments: [...r.comments, comment] } : r
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
          <Typography variant="h4">Bug reports</Typography>
          <Stack spacing={3}>
            {reports.map((item) => (
              <Card key={item.id}>
                <CardHeader
                  title={item.title}
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
