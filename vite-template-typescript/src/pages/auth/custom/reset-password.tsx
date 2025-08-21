import * as React from "react";
import { Helmet } from "react-helmet-async";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { ResetPasswordForm } from "@/components/auth/custom/reset-password-form";
import { SplitLayout } from "@/components/auth/split-layout";

const metadata = { title: `Reset password | Custom | Auth | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
	return (
		<React.Fragment>
			<Helmet>
				<title>{metadata.title}</title>
			</Helmet>
			<SplitLayout>
				<ResetPasswordForm />
			</SplitLayout>
		</React.Fragment>
	);
}
