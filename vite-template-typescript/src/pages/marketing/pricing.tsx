import * as React from "react";
import Divider from "@mui/material/Divider";
import { Helmet } from "react-helmet-async";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { Faqs } from "@/components/marketing/pricing/faqs";
import { PlansTable } from "@/components/marketing/pricing/plans-table";

const metadata = { title: `Pricing | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element {
	return (
		<React.Fragment>
			<Helmet>
				<title>{metadata.title}</title>
			</Helmet>
			<div>
				<PlansTable />
				<Divider />
				<Faqs />
			</div>
		</React.Fragment>
	);
}
