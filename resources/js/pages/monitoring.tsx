import MonitoringCard, { CardProps } from '@/components/MonitoringCard';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, Reading } from '@/types';
import { Head } from '@inertiajs/react';
import { useEcho } from '@laravel/echo-react';
import { SunMedium } from 'lucide-react';
import { JSX, useState } from 'react';
import 'reactflow/dist/style.css';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Monitoring',
        href: '/monitoring',
    },
];

export const icons: Record<string, { component: JSX.Element; color: string }> = {
    'Solar Array 1': {
        component: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    'Solar Array 2': {
        component: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    'Solar Array 3': {
        component: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    'Solar Array 4': {
        component: <SunMedium className="size-6" />,
        color: 'bg-green-600',
    },
    Refrigerator: {
        component: <SunMedium className="size-6" />,
        color: 'bg-blue-500',
    },
    'Living Room TV': {
        component: <SunMedium className="size-6" />,
        color: 'bg-purple-500',
    },
    'Washing Machine': {
        component: <SunMedium className="size-6" />,
        color: 'bg-orange-500',
    },
    'Microwave Oven': {
        component: <SunMedium className="size-6" />,
        color: 'bg-red-500',
    },
    'Wi-Fi & Devices': {
        component: <SunMedium className="size-6" />,
        color: 'bg-sky-500',
    },
};

const fromReadings = (readings: Reading[]) => {
    return readings.map((i, _, data) => {
        const sum = data.map((i) => i.active_power).reduce((accumulator, current) => accumulator + current);
        const progress = (i.active_power * 100) / sum;

        return {
            title: i.title,
            active_power: i.active_power,
            type: i.type,
            progress,
            icon: icons[i.title],
        };
    });
};

export default function Monitoring() {
    const [readings, setReadings] = useState<Reading[]>([]);

    useEcho('power', 'ReadingsSimulated', (e: { readings: Reading[] }) => {
        setReadings(e.readings);
    });

    const productionCircuits: CardProps[] = fromReadings(readings.filter((i) => i.type === 'production'));
    const consumptionCircuits: CardProps[] = fromReadings(readings.filter((i) => i.type === 'consumption'));

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Monitoring" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-lg p-4">
                <h2 className="text-xl font-semibold tracking-tight">Monitoring</h2>

                <h3 className="text-lg font-semibold tracking-tight">Production circuits</h3>
                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    {productionCircuits.map((item, index) => (
                        <MonitoringCard key={index} {...item} />
                    ))}
                </div>

                <h3 className="text-lg font-semibold tracking-tight">Consumption circuits</h3>
                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    {consumptionCircuits.map((item, index) => (
                        <MonitoringCard key={index} {...item} />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
