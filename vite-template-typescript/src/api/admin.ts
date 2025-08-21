import axios from "axios";

// Types for admin related resources
export interface AdminMetric {
  label: string;
  value: number;
}

export interface Comment {
  id: string;
  content: string;
  author: { name: string; avatar?: string };
  createdAt: Date;
}

export interface Notification {
  id: string;
  message: string;
  status: "open" | "resolved";
  comments: Comment[];
}

export interface BugReport {
  id: string;
  title: string;
  status: "open" | "resolved";
  comments: Comment[];
}

const api = axios.create({ baseURL: "/api/admin" });

export async function getAdminMetrics(): Promise<AdminMetric[]> {
  const response = await api.get("/metrics");
  return response.data;
}

export async function getNotifications(): Promise<Notification[]> {
  const response = await api.get("/notifications");
  return response.data.map((n: Notification) => ({
    ...n,
    comments: n.comments.map((c) => ({ ...c, createdAt: new Date(c.createdAt) })),
  }));
}

export async function updateNotificationStatus(
  id: string,
  status: "open" | "resolved"
): Promise<void> {
  await api.patch(`/notifications/${id}`, { status });
}

export async function addNotificationComment(
  id: string,
  content: string
): Promise<Comment> {
  const response = await api.post(`/notifications/${id}/comments`, { content });
  return { ...response.data, createdAt: new Date(response.data.createdAt) };
}

export async function getBugReports(): Promise<BugReport[]> {
  const response = await api.get("/bug-reports");
  return response.data.map((r: BugReport) => ({
    ...r,
    comments: r.comments.map((c) => ({ ...c, createdAt: new Date(c.createdAt) })),
  }));
}

export async function updateBugReportStatus(
  id: string,
  status: "open" | "resolved"
): Promise<void> {
  await api.patch(`/bug-reports/${id}`, { status });
}

export async function addBugReportComment(
  id: string,
  content: string
): Promise<Comment> {
  const response = await api.post(`/bug-reports/${id}/comments`, { content });
  return { ...response.data, createdAt: new Date(response.data.createdAt) };
}
