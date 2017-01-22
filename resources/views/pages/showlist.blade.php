@extends('layouts.app')

@section('content')
    @if($contacts->lastPage() < 1)
        <h2 class="emptyList">
            <p>EMPTY LIST</p>
            <a class="addButton" href="/record"><span>Add</span></a>
        </h2>
    @else
        <div class="contactsList" id="contactList">
            <form method="post" action="/showlist">
                {{ csrf_field() }}
                <input type="hidden" name="mainSortColumn" id="mainSortColumn"
                       value=<?= $sortValues['mainSortColumn'] ?>>
                <input type="hidden" name="sortDirectionMainColumn" id="sortDirectionMainColumn"
                       value=<?= $sortValues['sortDirectionMainColumn'] ?>>
                <input type="hidden" name="sortDirectionSecondaryColumn" id="sortDirectionSecondaryColumn"
                       value=<?= $sortValues['sortDirectionSecondaryColumn'] ?>>
                <input type="hidden" name="page" id="currentPage" value=<?= $sortValues['page'] ?>>
                <div class='formBackgroung' id='formBackgroung'>
                    <div class="headerContactsList">
                        <div class="sequence"></div>

                        <button name="last" class="sortButton" id="sortButtonLast" type="submit" value="last">
						<span id="last"
                              class=<?= ($sortValues['mainSortColumn'] == 'last') ? 'sortColorActiveButton' : 'sortColorNotActiveButton' ?>>Last Name
					    </span>
                        </button>

                        <button name="first" class="sortButton" id="sortButtonFirst" type="submit" value="first">
				        <span id="first"
                              class=<?= ($sortValues['mainSortColumn'] == 'first') ? 'sortColorActiveButton' : 'sortColorNotActiveButton' ?>>First Name
			            </span>
                        </button>

                        <div class="headerEmail">
                            <span>Email</span>
                        </div>

                        <div class="headerPhone">
                            <span>Cellular</span>
                        </div>

                        <div class="actions">
                            <span>Actions</span>
                        </div>
                    </div>
                    <div id="list">
                        @foreach ($contacts  as $contact)
                            <div class="list">
                                <div class="sequence"><?= $sortValues['offset'] . ".";
                                    $sortValues['offset']++; ?>
                                </div>
                                <div class="last" id="last">   {{ $contact->first }} </div>
                                <div class="first" id="first"> {{ $contact->last }} </div>
                                <div class="email" id="email"> {{ $contact->email }} </div>
                                <div class="phone" id="phone"> {{ $contact->phones[0]->phone }} </div>
                                <div class="actionsBottom">
                                    <div class="editButton">
                                        <div>
                                            <a href="/record/{{ $contact->id }}">
                                                <span>edit</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="actionDelete">
                                        <div class="deleteButton">
                                            <div>
                                                <a href="/remove/{{ $contact->id }}">
                                                    <span>view</span>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="xButton">
                                            <div>
                                                <a href="/remove/{{ $contact->id }}">
                                                    <span>X</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>

                    <div class="emptyBlock"></div>
                </div>

                <!-- pagination starts here !-->
                <?php if ($contacts->lastPage() > 1): ?>
                <div class="blockPagination" id="blockPagination">
                    <div class="pagination" id="pagination">
                        <div class="prev">
                            <button class="prevButton" id="prev" type="submit" name='page'
                                    value=<?= $contacts->currentPage() - 1 ?> <?= ($contacts->currentPage() == 1) ? 'disabled' : '' ?> >
                                <img src="/images/prev.png">
                                <span>previous</span>
                            </button>
                        </div>

                        <div class="numberPagesBlock" id="numberPagesBlock">
                            <span>Page:</span>
                            <?php if ($sortValues['firstShowPage'] > 1): ?>
                            <button type='submit' name='page' class='pageNumber' id="1" value="1">
                                1
                            </button>
                            <?php endif; ?>

                            <?php if ($sortValues['firstShowPage'] > NUMBER_DISPLAYED_PAGES_LINKS): ?>
                            <button type='submit' name='page' class="pageNumber"
                                    id="<?= ($sortValues['firstShowPage'] - NUMBER_DISPLAYED_PAGES_LINKS) < 1 ? 1 : $sortValues['firstShowPage'] - NUMBER_DISPLAYED_PAGES_LINKS ?>"
                                    value="<?= ($sortValues['firstShowPage'] - NUMBER_DISPLAYED_PAGES_LINKS) < 1 ? 1 : $sortValues['firstShowPage'] - NUMBER_DISPLAYED_PAGES_LINKS ?>">
                                ...
                            </button>
                            <?php endif; ?>

                            <?php while ($sortValues['firstShowPage'] <= $sortValues['lastShowPage']): ?>

                            <button type='submit' name='page'
                                    class="<?= ($sortValues['firstShowPage'] == $contacts->currentPage()) ? 'activePageNumber' : 'pageNumber' ?>"
                                    id="<?= $sortValues['firstShowPage'] ?>"
                                    value="<?= $sortValues['firstShowPage'] ?>">
                                <?= $sortValues['firstShowPage'] ?>
                            </button>
                            <?php $sortValues['firstShowPage']++; ?>
                            <?php endwhile;

                            if ($sortValues['lastShowPage'] < $contacts->lastPage()):
                            ?>
                            <button type='submit' name='page' class="pageNumber"
                                    id="<?= ($sortValues['lastShowPage'] == $contacts->lastPage()) ? $sortValues['lastShowPage'] : $sortValues['lastShowPage'] + 1 ?>"
                                    value="<?= ($sortValues['lastShowPage'] == $contacts->lastPage()) ? $sortValues['lastShowPage'] : $sortValues['lastShowPage'] + 1 ?>">
                                ...
                            </button>
                            <button type='submit' name='page' class="pageNumber"
                                    id="<?= $contacts->lastPage() ?>"
                                    value="<?= $contacts->lastPage() ?>">
                                <?= $contacts->lastPage() ?>
                            </button>
                            <?php endif; ?>
                        </div>

                        <div class="next">
                            <button class="nextButton" id="next" type="submit" name='page'
                                    value=<?= $contacts->currentPage() + 1 ?>
                                    <?= ($contacts->currentPage() == $contacts->lastPage()) ? 'disabled' : '' ?>>
                                <span>next</span>
                                <img src="/images/next.png">

                            </button>
                        </div>
                    </div>
                </div>

                <?php endif; ?>
            </form>
        </div>

    @endif
@endsection
