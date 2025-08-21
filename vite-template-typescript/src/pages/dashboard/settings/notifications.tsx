import * as React from "react";
import Stack from "@mui/material/Stack";
import Typography from "@mui/material/Typography";
import { Helmet } from "react-helmet-async";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { EmailNotifications } from "@/components/dashboard/settings/email-notifications";
import { PhoneNotifications } from "@/components/dashboard/settings/phone-notifications";

const metadata = { title: `Notifications | Settings | Dashboard | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
	return (
		<React.Fragment>
			<Helmet>
				<title>{metadata.title}</title>
			</Helmet>
			<Stack spacing={4}>
				<div>
					<Typography variant="h4">Notifications</Typography>
				</div>
				<Stack spacing={4}>
					<EmailNotifications />
					<PhoneNotifications />
				</Stack>
			</Stack>
		</React.Fragment>
	);
}
