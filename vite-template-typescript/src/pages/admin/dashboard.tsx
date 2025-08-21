import * as React from "react";
import Box from "@mui/material/Box";
import Grid from "@mui/material/Grid2";
import Stack from "@mui/material/Stack";
import Typography from "@mui/material/Typography";
import { Helmet } from "react-helmet-async";

import { appConfig } from "@/config/app";
import { getAdminMetrics, type AdminMetric } from "@/api/admin";
import type { Metadata } from "@/types/metadata";

const metadata = { title: `Dashboard | Admin | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
  const [metrics, setMetrics] = React.useState<AdminMetric[]>([]);

  React.useEffect(() => {
    getAdminMetrics()
      .then((data) => setMetrics(data))
      .catch(() => setMetrics([]));
  }, []);

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
          <Typography variant="h4">Admin dashboard</Typography>
          <Grid container spacing={3}>
            {metrics.map((metric) => (
              <Grid key={metric.label} size={{ xs: 12, sm: 6, md: 4 }}>
                <Box
                  sx={{
                    bgcolor: "var(--mui-palette-background-paper)",
                    borderRadius: 1,
                    p: 2,
                  }}
                >
                  <Typography color="text.secondary" variant="overline">
                    {metric.label}
                  </Typography>
                  <Typography variant="h5">{metric.value}</Typography>
                </Box>
              </Grid>
            ))}
          </Grid>
        </Stack>
      </Box>
    </React.Fragment>
  );
}
