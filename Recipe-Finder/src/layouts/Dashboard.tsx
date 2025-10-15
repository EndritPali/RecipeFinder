import DashboardHeader from "../features/admin/components/DashboardHeader";
import { DashboardOutlined } from '@ant-design/icons';
import '@/features/admin/scss/Dashboard.scss';
import { useState } from "react";
import DashboardSider from "../features/admin/components/DashboardSider";
import { Outlet } from "react-router-dom";
import ResponsiveDrawer from "../features/admin/templates/ResponsiveDrawer";
import { Button } from "antd";

export default function Dashboard() {
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);
  const showDrawer = () => setIsDrawerOpen(true);
  const closeDrawer = () => setIsDrawerOpen(false);

  return (
    <>
      <DashboardHeader />
      <div className="content-area">
        <Button
          icon={<DashboardOutlined />}
          className="drawer-activate"
          onClick={showDrawer} />
        <DashboardSider />
        <ResponsiveDrawer open={isDrawerOpen} onClose={closeDrawer} />
        <Outlet />
      </div>
    </>
  )
}