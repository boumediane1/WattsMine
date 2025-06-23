import MonitoringCard, { Circuit } from '@/components/MonitoringCard';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Microwave, Refrigerator, SunMedium, Tv, WashingMachine, Wifi } from 'lucide-react';
import 'reactflow/dist/style.css';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Monitoring',
        href: '/monitoring',
    },
];

export const data: Circuit[] = [
    {
        name: 'Solar Array 1',
        power: 922,
        on: true,
        type: 'production',
        icon: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    {
        name: 'Solar Array 2',
        power: 878,
        on: true,
        type: 'production',
        icon: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    {
        name: 'Solar Array 3',
        power: 76,
        on: true,
        type: 'production',
        icon: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    {
        name: 'Solar Array 4',
        power: 25,
        on: true,
        type: 'production',
        icon: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    {
        name: 'Refrigerator',
        power: 120,
        on: false,
        type: 'consumption',
        icon: <Refrigerator className="size-6" />,
        color: 'bg-blue-500',
    },
    {
        name: 'Living Room TV',
        power: 85,
        on: false,
        type: 'consumption',
        icon: <Tv className="size-6" />,
        color: 'bg-purple-500',
    },
    {
        name: 'Washing Machine',
        power: 691,
        on: false,
        type: 'consumption',
        icon: <WashingMachine className="size-6" />,
        color: 'bg-orange-500',
    },
    {
        name: 'Microwave Oven',
        power: 1100,
        on: true,
        type: 'consumption',
        icon: <Microwave className="size-6" />,
        color: 'bg-red-500',
    },
    {
        name: 'Wi-Fi & Devices',
        power: 137,
        on: true,
        type: 'consumption',
        icon: <Wifi className="size-6" />,
        color: 'bg-sky-500',
    },
];

export default function Monitoring() {
    const productionCircuits = data.filter((i) => i.type === 'production');
    const consumptionCircuits = data.filter((i) => i.type === 'consumption');

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Monitoring" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-lg p-4">
                <h2 className="text-xl font-semibold tracking-tight">Monitoring</h2>

                <h3 className="text-lg font-semibold tracking-tight">Production circuits</h3>
                <div className="grid grid-cols-4 gap-4">
                    {productionCircuits.map((item, index) => (
                        <MonitoringCard key={index} {...item} />
                    ))}
                </div>

                <h3 className="text-lg font-semibold tracking-tight">Consumption circuits</h3>
                <div className="grid grid-cols-4 gap-4">
                    {consumptionCircuits.map((item, index) => (
                        <MonitoringCard key={index} {...item} />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
