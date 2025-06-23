import {
    ColumnDef,
    ColumnFiltersState,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    SortingState,
    useReactTable,
    VisibilityState,
} from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';
import * as React from 'react';

import { Circuit } from '@/components/MonitoringCard';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { data } from '@/pages/monitoring';

export const columns: ColumnDef<Circuit>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    Name
                    <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => (
            <div className="flex items-center">
                <span className={`grid size-8 place-items-center rounded-lg text-white ${row.original.color ?? 'bg-blue-600'}`}>
                    {row.original.icon}
                </span>

                <div className="px-3 font-medium">{row.getValue('name')}</div>
            </div>
        ),
    },
    {
        accessorKey: 'power',
        header: ({ column }) => {
            return (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    Active power
                    <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => <div className="px-3 font-medium">{row.getValue('power')} W</div>,
    },
    {
        accessorKey: 'type',
        header: 'Type',
        cell: ({ row }) =>
            row.original.type === 'production' ? <Badge color="green" text={row.original.type} /> : <Badge color="blue" text={row.original.type} />,
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            return (
                <div className="flex justify-center">
                    {row.original.on ? (
                        <>
                            <Button className="rounded-none rounded-l bg-green-500 uppercase">on</Button>
                            <Button variant="outline" className="rounded-none rounded-r uppercase">
                                off
                            </Button>
                        </>
                    ) : (
                        <>
                            <Button variant="outline" className="rounded-none rounded-l uppercase">
                                on
                            </Button>
                            <Button className="rounded-none rounded-r bg-red-500 uppercase">off</Button>
                        </>
                    )}
                </div>
            );
        },
    },
];

export function DataTableDemo() {
    const [sorting, setSorting] = React.useState<SortingState>([]);
    const [columnFilters, setColumnFilters] = React.useState<ColumnFiltersState>([]);
    const [columnVisibility, setColumnVisibility] = React.useState<VisibilityState>({});
    const [rowSelection, setRowSelection] = React.useState({});

    const table = useReactTable({
        data,
        columns,
        onSortingChange: setSorting,
        onColumnFiltersChange: setColumnFilters,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        onColumnVisibilityChange: setColumnVisibility,
        onRowSelectionChange: setRowSelection,
        state: {
            sorting,
            columnFilters,
            columnVisibility,
            rowSelection,
        },
    });

    return (
        <div className="w-full">
            <div className="flex items-center py-4">
                <Input
                    placeholder="Filter names..."
                    value={(table.getColumn('email')?.getFilterValue() as string) ?? ''}
                    onChange={(event) => table.getColumn('email')?.setFilterValue(event.target.value)}
                    className="max-w-sm"
                />
            </div>
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => {
                                    return (
                                        <TableHead key={header.id}>
                                            {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                                        </TableHead>
                                    );
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell colSpan={columns.length} className="h-24 text-center">
                                    No results.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            <div className="flex items-center justify-end space-x-2 py-4">
                <div className="space-x-2">
                    <Button variant="outline" size="sm" onClick={() => table.previousPage()} disabled={!table.getCanPreviousPage()}>
                        Previous
                    </Button>
                    <Button variant="outline" size="sm" onClick={() => table.nextPage()} disabled={!table.getCanNextPage()}>
                        Next
                    </Button>
                </div>
            </div>
        </div>
    );
}

const Badge = ({ color, text }: { color: string; text: string }) => {
    if (color === 'green') {
        return (
            <span className="ring-inset/10 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 capitalize ring-1 ring-green-600/20 ring-inset">
                {text}
            </span>
        );
    } else if (color === 'blue') {
        return (
            <span className="ring-inset/20 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 capitalize ring-1 ring-blue-700/10 ring-inset">
                {text}
            </span>
        );
    }
};
