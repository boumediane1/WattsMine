import { Button } from '@/components/ui/button';
import { Reading } from '@/types';
import { InertiaFormProps } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { ArrowUpDown, Plug, Sun } from 'lucide-react';
import { icons } from './monitoring';

export const columns = (setData: InertiaFormProps<{ id: number; on: boolean }>['setData']): ColumnDef<Reading>[] => [
    {
        accessorKey: 'title',
        header: ({ column }) => {
            return (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    Title
                    <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => (
            <div className="flex items-center">
                <span className={`grid size-8 place-items-center rounded-lg text-white ${icons[row.original.title].color ?? 'bg-blue-600'}`}>
                    {icons[row.original.title].component}
                </span>

                <div className="px-3 font-medium">{row.getValue('title')}</div>
            </div>
        ),
    },
    {
        accessorKey: 'active_power',
        header: ({ column }) => {
            return (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    Active power
                    <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => <div className="px-3 font-medium">{row.original.active_power} W</div>,
    },
    {
        accessorKey: 'type',
        header: 'Type',
        cell: ({ row }) => (
            <div className="flex items-center gap-x-2">
                {row.original.type === 'production' ? <Sun className="size-4" /> : <Plug className="size-4" />}
                <span className="font-medium">{row.original.type === 'production' ? 'Production' : 'Consumption'}</span>
            </div>
        ),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            return (
                <div className="flex justify-center">
                    {row.original.on ? (
                        <>
                            <Button className="cursor-pointer rounded-none rounded-l bg-green-500 uppercase hover:bg-green-500">on</Button>

                            <Button
                                onClick={() => setData({ id: row.original.id, on: false })}
                                variant="outline"
                                className="hover:bg-background cursor-pointer rounded-none rounded-r uppercase"
                            >
                                off
                            </Button>
                        </>
                    ) : (
                        <>
                            <Button
                                onClick={() => setData({ id: row.original.id, on: true })}
                                variant="outline"
                                className="hover:bg-background cursor-pointer rounded-none rounded-l uppercase"
                            >
                                on
                            </Button>
                            <Button className="cursor-pointer rounded-none rounded-r bg-red-500 uppercase hover:bg-red-500">off</Button>
                        </>
                    )}
                </div>
            );
        },
    },
];
