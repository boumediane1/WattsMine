import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import 'reactflow/dist/style.css';
import SolarPVSystem from './SolarPVSystem';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid grid-cols-3">
                    <div className="col-span-2">content</div>

                    <div className="col-span-1">
                        <Card className="h-[476px]">
                            <CardHeader>
                                <CardTitle>Power Flow</CardTitle>
                                <CardDescription>Live energy usage and sources.</CardDescription>
                            </CardHeader>
                            <CardContent className="h-full">
                                <SolarPVSystem />
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
