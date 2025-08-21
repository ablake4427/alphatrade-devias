import * as React from "react";
import { Helmet } from "react-helmet-async";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { SplitLayout } from "@/components/auth/split-layout";
import { UpdatePasswordForm } from "@/components/auth/supabase/update-password-form";

const metadata = { title: `Update password | Supabase | Auth | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
	return (
		<React.Fragment>
			<Helmet>
				<title>{metadata.title}</title>
			</Helmet>
			<SplitLayout>
				<UpdatePasswordForm />
			</SplitLayout>
		</React.Fragment>
	);
}
