import { Card, CardContent } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { UtilityPole } from 'lucide-react';
import 'reactflow/dist/style.css';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Monitoring',
        href: '/monitoring',
    },
];

const Something = () => {
    return (
        <Card>
            <CardContent className="space-y-4">
                <div className="flex justify-between">
                    <div className="flex gap-x-4">
                        <span className="grid size-12 place-items-center rounded-lg bg-blue-600 text-white">
                            <UtilityPole className="size-6" />
                        </span>

                        <div>
                            <h3 className="text-xl font-bold">Utility Grid</h3>
                            <p className="text-sm text-gray-700">Consuming</p>
                        </div>
                    </div>

                    <div className="text-xl font-bold" style={{ fontFamily: 'Inter, sand-serif' }}>
                        2770 W
                    </div>
                </div>

                <Progress value={80} className="h-2" />
            </CardContent>
        </Card>
    );
};

export default function Monitoring() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Monitoring" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-lg p-4">
                <h2 className="text-xl font-semibold tracking-tight">Monitoring</h2>

                <h3 className="text-lg font-semibold tracking-tight">Production circuits</h3>
                <div className="grid grid-cols-4 gap-4">
                    <Something />
                    <Something />
                    <Something />
                    <Something />
                    <Something />
                    <Something />
                    <Something />
                </div>
            </div>
        </AppLayout>
    );
}
