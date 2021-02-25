<?php

namespace App\Modules\Orfeo\src\Exports;

use App\Modules\Orfeo\src\Models\Attachment;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Resources\FiledResource;
use App\Modules\Orfeo\src\Resources\FolderResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrfeoExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings
{
    use Exportable;

    /**
     * @var Request
     */
    private $orfeo;

    /**
     * OrfeoExport constructor.
     * @param Request $orfeo
     */
    public function __construct(Request $orfeo)
    {
        $this->orfeo = $orfeo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->getBuilder( $this->orfeo, Filed::query() );
    }

    public function headings(): array {
        return [
            'RADICADO',
            'FECHA DE RADICADO',
            'TÉRMINO',
            'FECHA DE RESPUESTA APROX.',
            'ASUNTO',
            'TIPO DE DOCUMENTO',
            'ESTADO',
            'LECTURA',
            'CARPETA',
            'DOCUMENTO DESTINATARIO',
            'NOMBRE DESTINATARIO',
            'DIRECCIÓN DESTINATARIO',
            'CIUDAD DESTINATARIO',
            'ESTADO/DPTO DESTINATARIO',
            'PAÍS DESTINATARIO',
            'USUARIO ACTUAL',
            'DEPENDENCIA ACTUAL',
            'ANEXOS',
            'ASOCIDOS',
            'INFORMADOS'
        ];
    }

    public function map($row): array
    {
        return [
            'id'                =>  isset( $row->radi_nume_radi ) ? (string) $row->radi_nume_radi : null,
            'created_at'        =>  isset( $row->radi_fech_radi ) ? $row->radi_fech_radi : null,
            'business_days'     =>  isset($row->document_type->business_days) ? (int) $row->document_type->business_days : null,
            'final_day'         =>  "{$this->getFinalDateAttribute($row)} DÍAS",
            'subject'           =>  isset( $row->ra_asun ) ? toUpper($row->ra_asun) : null,
            'document_type'     =>  isset($row->document_type->name) ? $row->document_type->name : null,
            'status'            =>  $this->getStatus($row),
            'read'              =>  isset( $row->radi_leido ) ? (bool) $row->radi_leido : null,
            'folder'            =>  isset( $row->folder->name ) ? $row->folder->name : null,
            'addressee_document'=>  isset( $row->radi_nume_iden ) ? (int) $row->radi_nume_iden : null,
            'addressee_full_name'=>  isset( $row->addressee_full_name ) ? $row->addressee_full_name : null,
            'address'            =>  isset( $row->radi_dire_corr ) ? toUpper($row->radi_dire_corr) : null,
            'city'               =>  isset($row->city->name) ? $row->city->name : null,
            'state'              =>  isset($row->city->state->name) ? $row->city->state->name : null,
            'country'            =>  isset($row->city->state->country->name) ? $row->city->state->country->name : null,
            'current_user_name'  => isset($row->user->name) ? $row->user->name : null,
            'current_dependency_name'  => isset($row->dependency->name) ? $row->dependency->name : null,
            'attachments_count' => isset( $row->attachments_count ) ? (int) $row->attachments_count : null,
            'associates_count'  => isset( $row->associates_count ) ? (int) $row->associates_count : null,
            'informed_count'    => isset( $row->informed_count ) ? (int) $row->informed_count : null,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * Get the final day
     *
     * @return string
     */
    public function getFinalDateAttribute($row)
    {
        $business_date = isset($row->document_type->business_days) ? (int) $row->document_type->business_days : 0;
        $pieces = explode(".", $row->radi_fech_radi);
        $date = isset($pieces[0]) ? Carbon::parse( $pieces[0] ) : null;
        return $date ? $date->addDays( (int) $business_date )->format('Y-m-d H:i:s') : null;
    }

    public function getStatus($row)
    {
        $status = Attachment::where('anex_radi_nume', $row->radi_nume_radi)
            ->where('anex_numero', 1)->first();
        return $this->getStatusName( isset( $status->anex_estado ) ? (int) $status->anex_estado : null );
    }

    public function getStatusName( $id = null )
    {
        switch ($id) {
            case 2:
                return 'CON IMAGEN PRINCIPAL';
                break;
            case 3:
                return 'IMPRESO';
                break;
            case 4:
                return 'ENVIADO';
                break;
            case 1:
            default:
                return 'RADICADO';
                break;
        }
    }

    public function getBuilder(Request $request, Builder $resource)
    {
        $start = $request->has('start_date')
            ? Carbon::parse( $request->get('start_date') )->startOfDay()
            : now()->startOfMonth();
        $final = $request->has('final_date')
            ? Carbon::parse( $request->get('final_date') )->endOfDay()
            : now()->endOfMonth();
        return $resource->when($request->has('document_type'), function ($query) use ( $request ) {
            return $query->whereIn('tdoc_codi', $request->get('document_type'));
        })->when( ($request->has('start_date') && $request->has('final_date') ), function ($query) use ( $start, $final ) {
            return $query->where([
                ['radi_fech_radi', '>=', $start],
                ['radi_fech_radi', '<=', $final],
            ]);
        })->when( $request->has('current_user_id'), function ($query) use ( $request ) {
            return $query->whereIn('radi_usua_actu', $request->get('current_user_id'));
        })->when( $request->has('read'), function ($query) use ( $request ) {
            $bool = filter_var($request->get('read'), FILTER_VALIDATE_BOOLEAN);
            return $query->where('radi_leido', $bool);
        })->when( $request->has('current_dependency_id'), function ($query) use ( $request ) {
            return $query->whereIn('radi_depe_actu', $request->get('current_dependency_id'));
        })->when( $request->has('folder'), function ($query) use ( $request ) {
            return $query->whereIn('carp_codi', $request->get('folder'));
        })->when( $request->has('query'), function ($query) use ( $request ) {
            return $query->where('radi_nume_radi', $request->get('query'));
        })->when( $request->has('where_has'), function ($query) use ( $request ) {
            foreach ( $request->get('where_has') as $relation ) {
                $query = $query->whereHas($relation);
            }
            return $query;
        })->when( $request->has('status'), function ($query) use ( $request ) {
            if ( (int) $request->get('status') == Filed::FILED ) {
                return $query->whereHas('attachments', function ($query) {
                    return $query->where('anex_estado', Filed::FILED);
                })->orWhereDoesntHave('attachments');
            } else {
                return $query->whereHas('attachments', function ($query) use ( $request ) {
                    return $query->where('anex_estado', (int) $request->get('status'));
                });
            }
        });
    }
}
