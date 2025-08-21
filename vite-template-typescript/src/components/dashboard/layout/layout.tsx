"use client";

import type * as React from "react";

import { dashboardConfig } from "@/config/dashboard";
import { useSettings } from "@/components/core/settings/settings-context";

import { HorizontalLayout } from "./horizontal/horizontal-layout";
import { VerticalLayout } from "./vertical/vertical-layout";

interface LayoutProps {
	children: React.ReactNode;
}

export function Layout(props: LayoutProps): React.JSX.Element {
	const { settings } = useSettings();
	const layout = settings.dashboardLayout ?? dashboardConfig.layout;

	if (layout === "horizontal") {
		return <HorizontalLayout {...props} />;
	}

	return <VerticalLayout {...props} />;
}
