import type { Order } from "@/components/dashboard/order/orders-table";

export interface OrderComment {
  id: string;
  message: string;
  createdAt: string;
}

export async function getOpenOrders(): Promise<Order[]> {
  const res = await fetch("/order/open");
  if (!res.ok) {
    throw new Error("failed to fetch open orders");
  }
  const data = await res.json();
  return data.map((o: any) => ({ ...o, createdAt: new Date(o.createdAt) }));
}

export async function getOrderHistory(): Promise<Order[]> {
  const res = await fetch("/order/history");
  if (!res.ok) {
    throw new Error("failed to fetch order history");
  }
  const data = await res.json();
  return data.map((o: any) => ({ ...o, createdAt: new Date(o.createdAt) }));
}

export async function getOrderComments(orderId: string): Promise<OrderComment[]> {
  const res = await fetch(`/orders/${orderId}/comments`);
  if (!res.ok) {
    throw new Error("failed to fetch comments");
  }
  return res.json();
}

export async function addOrderComment(orderId: string, message: string): Promise<void> {
  const res = await fetch(`/orders/${orderId}/comments`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ message }),
  });
  if (!res.ok) {
    throw new Error("failed to add comment");
  }
}

export async function updateOrderStatus(orderId: string, status: Order["status"]): Promise<void> {
  const res = await fetch(`/orders/${orderId}/status`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ status }),
  });
  if (!res.ok) {
    throw new Error("failed to update status");
  }
}

