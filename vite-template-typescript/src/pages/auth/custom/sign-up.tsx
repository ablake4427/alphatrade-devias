import * as React from "react";
import { Helmet } from "react-helmet-async";
import { useNavigate } from "react-router-dom";

import type { Metadata } from "@/types/metadata";
import { appConfig } from "@/config/app";
import { paths } from "@/paths";
import { logger } from "@/lib/default-logger";
import { useAuth } from "@/components/auth/custom/auth-context";
import { SignUpForm } from "@/components/auth/custom/sign-up-form";
import { SplitLayout } from "@/components/auth/split-layout";

const metadata = { title: `Sign up | Custom | Auth | ${appConfig.name}` } satisfies Metadata;

export function Page(): React.JSX.Element | null {
	const { isAuthenticated, isLoading } = useAuth();
	const navigate = useNavigate();

	React.useEffect(() => {
		if (isLoading || !isAuthenticated) {
			return;
		}

		logger.debug("[Sign up] User is authenticated, redirecting to dashboard");
		navigate(paths.dashboard.overview);
	}, [isAuthenticated, isLoading, navigate]);

	if (isLoading || isAuthenticated) {
		return null;
	}

	return (
		<React.Fragment>
			<Helmet>
				<title>{metadata.title}</title>
			</Helmet>
			<SplitLayout>
				<SignUpForm />
			</SplitLayout>
		</React.Fragment>
	);
}
