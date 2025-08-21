import * as React from "react";
import { Outlet } from "react-router-dom";
import type { RouteObject } from "react-router-dom";

import { AuthGuard } from "@/components/auth/auth-guard";
import { Layout as DashboardLayout } from "@/components/dashboard/layout/layout";

export const route: RouteObject = {
  path: "admin",
  element: (
    <AuthGuard>
      <DashboardLayout>
        <Outlet />
      </DashboardLayout>
    </AuthGuard>
  ),
  children: [
    {
      path: "dashboard",
      lazy: async () => {
        const { Page } = await import("@/pages/admin/dashboard");
        return { Component: Page };
      },
    },
    {
      path: "notifications",
      lazy: async () => {
        const { Page } = await import("@/pages/admin/notifications");
        return { Component: Page };
      },
    },
    {
      path: "request-report",
      lazy: async () => {
        const { Page } = await import("@/pages/admin/request-report");
        return { Component: Page };
      },
    },
  ],
};
